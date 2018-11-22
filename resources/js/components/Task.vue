<template>
  <div class="uk-margin-top">
    <div uk-grid>
      <div class="uk-width-1-2">
        <h4>Available groups:</h4>
        <table class="uk-table uk-table-striped">
          <thead>
          <tr>
            <th>#</th>
            <th>items</th>
            <th>min count</th>
            <th class="uk-text-right">add</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="group in itemGroups">
            <td>{{group.id}}</td>
            <td>{{group.items.length}}</td>
            <td>{{group.min_count}}</td>
            <td class="uk-text-right">
              <ul class="uk-iconnav uk-flex-right">
                <li><a @click="add(group.id)" href="#" uk-icon="icon: plus"></a></li>
              </ul>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
      <div class="uk-width-1-2">
        <h4>Groups in task:</h4>
        <table class="uk-table uk-table-striped">
          <thead>
          <tr>
            <th>#</th>
            <th>items</th>
            <th>min count</th>
            <th class="uk-text-right">remove</th>
          </tr>
          </thead>
          <tbody>
          <tr v-for="group in task.items">
            <td>{{group.id}}</td>
            <td>{{group.items.length}}</td>
            <td>{{group.min_count}}</td>
            <td class="uk-text-right">
              <ul class="uk-iconnav uk-flex-right">
                <li><a @click="remove(group.id)" href="#" uk-icon="icon: minus"></a></li>
              </ul>
            </td>
          </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
  import axios from 'axios'

  export default {
    name: 'AddTask',
    data () {
      return {
        itemGroups: [],
        task: {items: [], name: ''},
      }
    },
    mounted () {
      axios.get('/api/groups-with-items')
        .then(response => {
          this.itemGroups = response.data
        })
    },
    methods: {
      add (id) {
        this.task.items.unshift(this.getItemFromArrayById(this.itemGroups, id))
      },
      remove (id) {
        this.itemGroups.unshift(this.getItemFromArrayById(this.task.items, id))
      },
      getItemFromArrayById (array, id) {
        let index = array.findIndex(item => {
          return item.id === id
        })
        if (index === -1) {
          throw new Error('item not found')
        }
        let item = array[index]
        array.splice(index, 1)
        return item
      }
    }
  }
</script>

<style scoped>

</style>