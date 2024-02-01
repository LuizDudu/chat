<!DOCTYPE html>
<html lang="en">
<head>
    <?php if(env('DONT_USE_WSS') !== "true") { ?>
    <!-- Google tag (gtag.js) -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-DWP9K96V2H"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());

      gtag('config', 'G-DWP9K96V2H');
    </script>
    <?php } ?>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="A project from Luiz Eduardo's portfolio, this is a online chat using PHP, Swoole, Websockets and Tailwind CSS">
    <link rel='icon' href='favicon.ico' type='image/x-icon'>
    <link rel='shortcut icon' href='favicon.ico' type='image/x-icon'>

    <title>Luiz Eduardo's Portfolio - Online Chat</title>

    <!-- Fonts -->
    <link href="output.css" rel="stylesheet">
    <script type="text/javascript">
      const websocket = new WebSocket(
        `<?php echo env('DONT_USE_WSS') === "true" ? "ws" : "wss" ?>://${document.location.host}/chat`
      );

      document.websocket = websocket;
    </script>
    <script type="text/javascript" src="index.js"></script>
</head>
<body class="font-sans bg-[#9D80B1] text-black flex flex-col items-center gap-10">
<header class="mt-4">
    <h1 class="text-3xl red-500 text-[#7E22CE] opacity-90">
        Welcome to online chat
    </h1>
</header>

<main class="min-h-[40rem] w-11/12 bg-[#FFE9FF] border-4 rounded-2xl border-[#7E22CE] shadow-2xl shadow-purple-800 p-2 grid">
    <section aria-label="Chat messages"
             class="max-h-[600px] h-[600px] overflow-y-auto flex-grow p-4">
        <output id="messagesBox" class="grid gap-y-4">
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
        </output>

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
