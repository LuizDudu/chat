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
  websocket.binaryType = "arraybuffer";

  let timer = 0;
  let timerInterval = setInterval(() => {
    if (websocket.readyState === WebSocket.CLOSED) {
      clearInterval(timerInterval);
    }
    timer++
    if (timer >= 50) {
      websocket.send(new Blob([0x09], { type: "application/octet-stream" }));
      timer = 0;
    }
  }, 1000);

  const messageTemplate = document.getElementById("messageTemplate");
  const messagesBox = document.getElementById("messagesBox");

  const form = document.getElementById('sendMessageForm');

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
    timer = 0;
    if (event.data instanceof ArrayBuffer) {
      return;
    }

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
