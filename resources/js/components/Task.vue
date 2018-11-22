<template>
  <div>
    <div class="uk-margin-top">
      <div uk-grid>
        <div class="uk-width-1-2">
          <div class="uk-margin">
            <input class="uk-input" v-model="task.title" type="text" placeholder="Bag Name">
          </div>
          <div class="uk-margin">
            <input class="uk-input" v-model="task.volume" type="number" placeholder="Bag Volume">
          </div>
        </div>
        <div class="uk-width-1-2">
          <div class="uk-margin">
            <div class="uk-width-1-2"></div>
            <div class="uk-width-1-2">
              <button v-if="!isSaved" @click="save" class="uk-button uk-button-primary">Save</button>
              <button v-if="isSaved" @click="run(task.id)" class="uk-button uk-button-primary">Calculate</button>
            </div>
          </div>
        </div>
      </div>
    </div>
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
            <tr v-for="group in task.groups">
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
    <div id="modal-container" class="uk-modal-container" ref="modal">
      <div class="uk-modal-dialog uk-modal-body" v-if="result">
        <button class="uk-modal-close-default" type="button" uk-close></button>
        <h2 class="uk-modal-title">Result</h2>
        <template v-for="group in result">
          <h4> {{group.length}} items in group</h4>
          <table class="uk-table uk-table-striped">
            <thead>
            <tr>
              <th>#</th>
              <th>Name</th>
              <th>Volume</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="item in group">
              <td>{{item.id}}</td>
              <td>{{item.name}}</td>
              <td>{{item.volume}}</td>
            </tr>
            </tbody>
          </table>
        </template>
        <h4 class="uk-text-right">Total volume: {{totalVolume}}</h4>
      </div>
    </div>

  </div>
</template>

<script>
  import axios from 'axios'
  import { notification, modal } from 'uikit'

  export default {
    computed: {
      id: {
        get () {
          return this.$route.params.id
        }
      }
    },
    name: 'Task',
    data () {
      return {
        itemGroups: [],
        task: {groups: [], title: '', volume: 0, id: 0},
        isSaved: false,
        result: false,
        totalVolume: 0
      }
    },
    watch: {
      task: {
        handler (value) {
          this.isSaved = false
        },
        deep: true
      },
      $route (newValue) {
        this.init(newValue.params.id)
      },
      result (newValue) {
        if (newValue) {

        }
      }
    },
    mounted () {
      this.init(this.id)
    },
    methods: {
      init (id) {
        this.result = false
        let promises = []
        if (id) {
          promises.push(
            axios.get('/api/task/' + id)
              .then(response => {
                this.task = response.data
              })
              .catch(e => {
                notification({message: e.message, status: 'danger'})
              })
          )
        } else {
          console.info(this.task)
          this.task = {groups: [], title: '', volume: 0, id: 0}
        }
        promises.push(
          axios.get('/api/groups-with-items')
            .then(response => {
              this.itemGroups = response.data
            })
            .catch(e => {
              notification({message: e.message, status: 'danger'})
            })
        )

        Promise.all(promises)
          .then(results => {
            this.task.groups.forEach(group => {
              this.takeItemFromArrayById(this.itemGroups, group.id)
              this.isSaved = true
            })
          })
      },
      add (id) {
        this.task.groups.unshift(this.takeItemFromArrayById(this.itemGroups, id))
      },
      remove (id) {
        this.itemGroups.unshift(this.takeItemFromArrayById(this.task.groups, id))
      },
      takeItemFromArrayById (array, id) {
        this.isSaved = false
        let index = array.findIndex(item => {
          return item.id === id
        })
        if (index === -1) {
          throw new Error('item not found')
        }
        let item = array[index]
        array.splice(index, 1)
        return item
      },
      run (id) {
        axios.post('/api/task/run/' + id, {})
          .then(response => {
            this.result = response.data
            let volume = 0
            response.data.forEach(group => {
              group.forEach(item => {
                volume += parseFloat(item.volume)
              })
            })
            this.totalVolume = volume.toFixed(2)
            modal(this.$refs.modal).show()
          })
          .catch(e => {
            console.info(e)
            notification({message: e.response.data.message, status: 'danger'})
          })
      },
      save () {
        let request = {...this.task}
        request.groups = []
        this.task.groups.forEach(item => {
          request.groups.push(item.id)
        })
        if (this.validate(request)) {
          axios.post('/api/task/' + (this.id ? this.id : 0), request)
            .then(response => {
              this.$router.push({name: 'EditTask', params: {id: response.data.id}})
              this.isSaved = true
            })
            .catch(e => {
              notification({message: e.message, status: 'danger'})
            })
        }
      },
      validate (request) {
        if (!request.groups.length) {
          notification({message: 'Items can not be empty!', status: 'danger'})
          return false
        }
        if (!request.title.length) {
          notification({message: 'Title can not be empty!', status: 'danger'})
          return false
        }
        if (!request.volume) {
          notification({message: 'Volume must be > 0!', status: 'danger'})
          return false
        }
        return true
      }
    }
  }
</script>

<style scoped>

</style>