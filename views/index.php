<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Online Chat</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      const colors = [
        "red",
        "orange",
        "amber",
        "yellow",
        "lime",
        "green",
        "emerald",
        "teal",
        "cyan",
        "sky",
        "blue",
        "indigo",
        "violet",
        "purple",
        "fuchsia",
        "pink",
        "rose",
      ];

      const ranges = ["500", "600", "700", "800", "900"];

      document.addEventListener('DOMContentLoaded', _ => {
        websocket = new WebSocket(`ws://${document.location.host}`);

        const messageTemplate = document.getElementById("messageTemplate");
        const messagesBox = document.getElementById("messagesBox");

        const form = document.getElementById('sendMessageForm');
        const messageInput = form.querySelector("#message");

        let nickname = localStorage.getItem("nickname");

        while (nickname === null || nickname.length === 0 || nickname.length > 16) {
          nickname = prompt("Please enter your nickname (Max 16 characters)");
        }

        localStorage.setItem("nickname", nickname);

        form.addEventListener("submit", function (e) {
          e.preventDefault();
          const form = e.currentTarget;

          const formData = new FormData(form);

          const message = Object.freeze({
            "nickname": localStorage.getItem("nickname"),
            "message": formData.get('message'),
          });

          websocket.send(JSON.stringify(message));

          form.reset();
          form.focus();
        });

        websocket.addEventListener('message', function (event) {
          const data = JSON.parse(event.data);
          const newMessage = messageTemplate.content.cloneNode(true);

          const date = new Date(0);
          date.setUTCSeconds(data.date_time);

          newMessage.querySelector(".messages-box-datetime").innerText = date.toLocaleString();
          newMessage.querySelector(".messages-box-nickname").innerText = data.nickname;
          newMessage.querySelector(".messages-box-user-message").innerText = data.message;

          let randomBorderColor = 'border-' + tailwindRandomColorClass()

          newMessage
            .querySelector(".sent-message")
            .classList.add(randomBorderColor);

          messagesBox.append(newMessage);

          messagesBox
            .querySelector(".sent-message:last-child")
            .scrollIntoView({behavior: "smooth"});
        });

        function tailwindRandomColorClass() {
          const colorIndex = randomInt(0, colors.length - 1);
          const rangeIndex = randomInt(0, ranges.length - 1);

          return `${colors[colorIndex]}-${ranges[rangeIndex]}`;

          function randomInt(min, max) {
            return Math.floor(Math.random() * (max - min) + min);
          }
        }
      })
    </script>
</head>
<body class="bg-[#9D80B1] text-black flex flex-col items-center gap-10">
<header class="mt-4">
    <h1 class="text-3xl text-[#7E22CE] opacity-90">
        Welcome to online chat
    </h1>
</header>

<main class="min-h-[40rem] w-11/12 bg-[#FFE9FF] border-4 rounded-2xl border-[#7E22CE] shadow-2xl shadow-purple-800 p-2 grid">
    <section aria-label="{{ __('Chat messages') }}"
             class="max-h-[600px] h-[600px] overflow-y-auto flex-grow p-4">

        <div id="messagesBox" class="grid gap-y-4">
            <template id="messageTemplate">
                <div class="border-2 pl-2 rounded sent-message">
                    <span class="messages-box-datetime opacity-50">
                    </span>

                    <div>
                        <span>
                            <b class="messages-box-nickname">LuizDudu</b>
                        </span>

                        <p class="inline messages-box-user-message">
                            Message
                        </p>
                    </div>
                </div>
            </template>
        </div>

        <noscript class="min-w-max">
            <em class="text-[#7E22CE] min-w-max">
                You need javascript to use the chat
            </em>
        </noscript>
    </section>

    <hr class="mt-auto border-[1px] border-opacity-10 border-black"/>

    <form method="POST" action="" id="sendMessageForm"
          class="h-20 mt-auto mb-2 flex items-end gap-2">
        <div class="inline-grid flex-grow h-20 w-[75%]">
            <label for="message" class="text-opacity-60 text-black mt-auto">Message</label>
            <input type="text" name="message" id="message" autocomplete="off" autofocus
                   placeholder="Enter your message here"
                   class="rounded-2xl shadow shadow-black indent-4 placeholder-[#7E22CE] w-full
                       active:border-[#7E22CE] active:outline-2
                       focus-visible:outline-[#7E22CE] focus-visible:outline-2
                       focus:outline-[#7E22CE] focus:outline-2 focus:scale-[100.5%]">
        </div>

        <button type="submit" class="h-10 bg-[#7E22CE] text-white rounded w-[25%] max-w-[200px]">
            Send
        </button>
    </form>
</main>

<footer>
    <em class="text-[#7E22CE] opacity-90">
        Have fun
    </em>
</footer>
</body>
</html>
