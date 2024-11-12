import IndexField from './components/IndexField'
import DetailField from './components/DetailField'
import FormField from './components/FormField'

Nova.booting((app, store) => {
  app.component('index-deserialisedjson', IndexField)
  app.component('detail-deserialisedjson', DetailField)
  app.component('form-deserialisedjson', FormField)
})
