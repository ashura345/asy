<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Chatbot</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50 flex flex-col h-full" x-data="{ messages: [], input: '' }" x-init="messages.push('Halo! Ada yang bisa saya bantu? 😊')">
  <div class="flex-1 overflow-y-auto p-4 space-y-2">
    <template x-for="msg in messages">
      <div x-text="msg" class="bg-green-100 p-2 rounded-lg"></div>
    </template>
  </div>
  <div class="flex p-2 border-t">
    <input x-model="input" @keydown.enter="messages.push('Kamu: ' + input); input='';"
      placeholder="Ketik pesan..." class="flex-1 border rounded-lg px-3 py-2 focus:outline-none">
    <button @click="messages.push('Kamu: ' + input); input='';"
      class="ml-2 bg-green-500 text-white px-4 py-2 rounded-lg">Kirim</button>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
</body>
</html>
