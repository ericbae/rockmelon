<template>
  <div class="">
    <div class="copyright text-center">
      &copy; {{ thisYear }} All Rights Reserved - {{ getAppName }}
    </div>

    <div class="menu mt-2 text-center">
      <a :href="getAppUrl">Home</a>
      <a href="/auth/login" class="ml-2">Login</a>
      <a href="/auth/register" class="ml-2">Register</a>
      <a href="mailto:hello@rockmelon.app" class="ml-2">Contact us</a>
      <!-- <a href="https://www.newsy.co/privacy-policy" class="ml-2" target="_blank">Privacy Policy</a>
      <a :href="getAppUrl + '/terms-of-service'" class="ml-2" target="_blank">Terms of Service</a> -->
    </div>
  </div>
</template>

<style scoped>
  .copyright {
    @apply text-gray-600
  }

  .menu a {
    @apply no-underline text-gray-900;
  }

  .menu a:hover {
    @apply underline;
  }
</style>