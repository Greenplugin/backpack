<template>
  <div>
    <div v-if="tasks.length">
      <h2>Tasks</h2>
      <table class="uk-table uk-table-striped uk-margin-top">
        <thead>
        <tr>
          <th>#</th>
          <th>title</th>
          <th>volume</th>
          <th>groups</th>
          <th class="uk-text-right">edit</th>
        </tr>
        </thead>
        <tbody>
        <tr v-for="task in tasks">
          <td>{{task.id}}</td>
          <td>{{task.title}}</td>
          <td>{{task.volume}}</td>
          <td>{{task.groups.length}}</td>
          <td class="uk-text-right">
            <ul class="uk-iconnav uk-flex-right">
              <li>
                <router-link :to="{ name: 'EditTask', params: {id: task.id}}" uk-icon="icon: pencil"></router-link>
              </li>
            </ul>
          </td>
        </tr>
        </tbody>
      </table>
    </div>
    <div class="display-height uk-flex-center uk-flex-middle uk-flex uk-flex-column" v-if="!tasks.length">
      <h2>Tasks not found</h2>
      <router-link class="uk-button uk-button-large uk-button-secondary" :to="{ name: 'AddTask'}">Add Task</router-link>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'

  export default {
    name: 'Home',
    data () {
      return {
        tasks: []
      }
    },
    beforeRouteUpdate (to, from, next) {
      this.init()
      next()
    },
    mounted () {
      this.init()
    },
    methods: {
      init () {
        axios.get('api/tasks')
          .then(response => {
            this.tasks = response.data
          })
      }
    }
  }
</script>

<style scoped>

</style>