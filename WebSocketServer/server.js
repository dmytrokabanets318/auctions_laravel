var app = require("http").createServer();

const io = require("socket.io")(app, {
  allowEIO3: true,
  cors: {
    origin: "http://localhost:3000",
    methods: ["GET", "POST"],
    allowedHeaders: ["my-custom-header"],
    credentials: true
  }
});

app.listen(8080, function() {
  console.log("listening on *:8080");
});


var loggedUsers = require("./loggedusers.js");

io.on("connection", function(socket) {
  console.log("client has connected (socket ID = " + socket.id + ")");

  socket.on("user_enter", function(user) {
    console.log("User-> ",user);
    console.log("Socket-> ", socket);
    if (user) {
      socket.join("department_" + user.department_id);
      loggedUsers.addUserInfo(user, socket.id);
    }
  });

});
