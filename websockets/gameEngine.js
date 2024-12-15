exports.createGameEngine = () => {
  const initGame = (gameFromDB) => {
    gameFromDB.gameStatus = null;
    // null -> game has not started yet
    // 0 -> game has started and running
    // 1 -> player 1 is the winner
    // 2 -> player 2 is the winner
    // 3 -> draw
    gameFromDB.currentPlayer = gameFromDB.created_user_id;
    // Randomized numbers
    // gameFromDB.board = Array.from({ length: 6 }, (_, i) => i + 1).flatMap(n => [n, n]).sort(() => Math.random() - 0.5);

    // Static numbers for testing
    gameFromDB.board = [1, 1, 2, 2, 3, 3, 4, 4, 5, 5, 6, 6];
    gameFromDB.flipped = Array.from({ length: 12 }, () => false);
    gameFromDB.currentlyFlipped = [];
    gameFromDB.pairsFound = 0;
    gameFromDB.pairsByPlayer1 = 0;
    gameFromDB.pairsByPlayer2 = 0;
    gameFromDB.pairs = [];
    gameFromDB.moves = 0;
    gameFromDB.isGameWon = false;
    gameFromDB.isFlipping = false;
    return gameFromDB;
  };

  // ------------------------------------------------------
  // Actions / Methods
  // ------------------------------------------------------

  // Flip the selected card
  const flipCard = (game, index) => {
    // Prevent further flips if cards are flipping back
    if (game.isFlipping || game.flipped[index]) {
      return;
    }

    game.flipped[index] = true;
    game.currentlyFlipped.push(index);

    if (game.currentlyFlipped.length === 2) {
      const [index1, index2] = game.currentlyFlipped;
      const card1 = game.board[index1];
      const card2 = game.board[index2];

      if (card1 === card2) {
        game.pairsFound++;
        game.currentPlayer === game.player1
          ? game.pairsByPlayer1++
          : game.pairsByPlayer2++;
        game.pairs.push([index1, index2]);
        game.currentlyFlipped = []; // Clear immediately for matched pairs
      } else {
        // Block interactions and flip back mismatched cards
        game.isFlipping = true;
        setTimeout(() => {
          game.flipped[index1] = false;
          game.flipped[index2] = false;
          game.currentlyFlipped = []; // Clear after flipping back
          game.isFlipping = false; // Allow interactions again
        }, 750);
      }

      game.moves++;
      changeGameStatus(game);
    }
    return game;
  };

  // Show hint
  const showHint = (game) => {
    const unflippedPairs = [];

    for (let i = 0; i < game.board.length; i++) {
      if (!game.flipped[i]) {
        for (let j = i + 1; j < game.board.length; j++) {
          if (!game.flipped[j] && game.board[i] === game.board[j]) {
            unflippedPairs.push([i, j]);
          }
        }
      }
    }

    if (unflippedPairs.length > 0) {
      const [index1, index2] = unflippedPairs[0];
      game.flipped[index1] = true;
      game.flipped[index2] = true;
      setTimeout(() => {
        game.flipped[index1] = false;
        game.flipped[index2] = false;
      }, 750);
    } else {
      console.log("No unflipped pairs found.");
    }

    return game;
  };

  // Check if the board is complete and change the gameStatus accordingly
  const changeGameStatus = (game) => {
    // Change game status based on who won
    if (game.pairsFound === game.board.length / 2) {
      //game is finished but we need to see who won
      if (game.pairsByPlayer1 > game.pairsByPlayer2) {
        game.gameStatus = 1;
      } else if (game.pairsByPlayer1 < game.pairsByPlayer2) {
        game.gameStatus = 2;
      } else {
        game.gameStatus = 3;
      }
    } else if (
      game.currentlyFlipped.length === 2 ||
      game.currentlyFlipped.length === 0
    ) {
      game.gameStatus = 0; //Game is running
      game.currentPlayer =
        game.currentPlayer === game.player1 ? game.player2 : game.player1;
    } else {
      game.gameStatus = game.player1 === game.currentPlayer ? 1 : 2;
    }
  };

  // returns whether the game as ended or not
  const gameEnded = (game) => game.gameStatus > 0;

  // Plays a specific piece of the game (defined by its index)
  // Returns true if the game play is valid or an object with an error code and message otherwise
  const play = (game, index, playerSocketId) => {
    if (
      playerSocketId != game.player1SocketId &&
      playerSocketId != game.player2SocketId
    ) {
      return {
        errorCode: 10,
        errorMessage: "You are not playing this game!",
      };
    }
    if (gameEnded(game)) {
      return {
        errorCode: 11,
        errorMessage: "Game has already ended!",
      };
    }
    if (
      (game.currentPlayer == game.player1 &&
        playerSocketId != game.player1SocketId) ||
      (game.currentPlayer == game.player2 &&
        playerSocketId != game.player2SocketId)
    ) {
      return {
        errorCode: 12,
        errorMessage: "Invalid play: It is not your turn!",
      };
    }
    if (game.flipped[index] === true) {
      return {
        errorCode: 13,
        errorMessage: "Invalid play: card already flipped!",
      };
    }
    flipCard(game, index);
    return true;
  };

  // One of the players quits the game. The other one wins the game
  const quit = (game, playerSocketId) => {
    if (
      playerSocketId != game.player1SocketId &&
      playerSocketId != game.player2SocketId
    ) {
      return {
        errorCode: 10,
        errorMessage: "You are not playing this game!",
      };
    }
    if (gameEnded(game)) {
      return {
        errorCode: 11,
        errorMessage: "Game has already ended!",
      };
    }
    game.gameStatus = playerSocketId == game.player1SocketId ? 2 : 1;
    game.status = "E";
    return true;
  };

  // Check if socket can close the game (game must have ended and player must belong to game)
  const close = (game, playerSocketId) => {
    if (
      playerSocketId != game.player1SocketId &&
      playerSocketId != game.player2SocketId
    ) {
      return {
        errorCode: 10,
        errorMessage: "You are not playing this game!",
      };
    }
    if (!gameEnded(game)) {
      return {
        errorCode: 14,
        errorMessage: "Cannot close a game that has not ended!",
      };
    }
    return true;
  };

  return {
    initGame,
    gameEnded,
    play,
    quit,
    close,
  };
};
