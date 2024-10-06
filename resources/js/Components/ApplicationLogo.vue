<template>
    <img :src="logoUrl" alt="Logo" class="logo" />
  </template>

  <script setup>
  const logoUrl = '/logo';
  </script>

  <style scoped>
  .logo {
    width: 100px;
    height: auto;
  }
  </style>
