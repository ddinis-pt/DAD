const { time } = require("console");

const httpServer = require("http").createServer();
const io = require("socket.io")(httpServer, {
  cors: {
    origin: "*",
    methods: ["GET", "POST"],
    credentials: true,
  },
});

const PORT = process.env.APP_PORT || 8086;

httpServer.listen(PORT, () => {
  console.log(`listening on localhost:${PORT}`);
});

io.on('connection', (socket) => {
  console.log('New connection:', socket.id);

  // ***********  Login  *********** //
  socket.on('login', (user) => {
    socket.data.user = user
    if (user && user.id) {
      socket.join('user_' + user.id)
    }
    console.log('User logged:', user)
  })

  // ***********  Logout  *********** //
  socket.on('logout', (user) => {
    if (user && user.id) {
      socket.leave('user_' + user.id)
    }
    socket.data.user = undefined
    console.log('User logout:', user)
  })

  // ***********  Chat Global   *********** //
  socket.on('chatMessage', (message) => {
    const payload = {
      user: socket.data.user,
      message: message,
    }
    console.log('Message received: ', socket.data.user.name, ':' ,message)
    io.sockets.emit('chatMessage', payload)
  })

  // ***********  Chat Private   *********** //
  socket.on('privateMessage', (clientMessageObj, callback) => {
      const destinationRoomName = 'user_' + clientMessageObj?.destinationUser?.id
      // Check if the destination user is online
      if (io.sockets.adapter.rooms.get(destinationRoomName)) {
          const payload = {
          user: socket.data.user,
          message: clientMessageObj.message
        }
        // send the "privateMessage" to the destination user (using "his" room)
        io.to(destinationRoomName).emit('privateMessage', payload)
        if (callback) {
          callback({success: true})
        }
      } else {
        if (callback) {
          callback({
            errorCode: 1,
            errorMessage: `User "${clientMessageObj?.destinationUser?.name}" is not online!`
          })
        }
      }
    }) 

})