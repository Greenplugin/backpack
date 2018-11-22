<template>
  <div>
    <nav class="uk-navbar-container navbar-primary uk-margin-bottom" uk-navbar>
      <div class="uk-navbar-left">
        <ul class="uk-navbar-nav">
          <li class="uk-parent">
            <router-link :to="{ name: 'home'}">Home</router-link>
          </li>
        </ul>
      </div>
      <div class="uk-navbar-right">
        <ul class="uk-navbar-nav">
          <li class="uk-parent">
            <router-link :to="{ name: 'AddTask'}">Add Task</router-link>
          </li>
        </ul>
      </div>
    </nav>
    <div class="uk-container">
      <router-view></router-view>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'

  export default {
    name: 'App',
  }
</script>

<style scoped>

</style>