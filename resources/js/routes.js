import Home from './components/Home.vue'
import AddTask from './components/Task'

export default [
  {
    path: '/',
    name: 'home',
    component: Home
  },
  {
    path: '/task/add',
    name: 'AddTask',
    component: AddTask
  },
  {
    path: '/task/edit/:id',
    name: 'EditTask',
    component: AddTask
  },
]
