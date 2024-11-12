<template>
  <div>
    <table>
      <thead>
        <tr>
          <th>Order</th>
          <th>Image</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(image, index) in images" :key="image.id">
          <td>{{ image.order }}</td>
          <td>
            <img :src="image.filename" :alt="image.filename" />
          </td>
          <td>
            <button @click="moveUp(index)" :disabled="index === 0">Up</button>
            <button @click="moveDown(index)" :disabled="index === images.length - 1">Down</button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  props: {
    images: {
      type: Array,
      required: true,
    },
  },
  methods: {
    moveUp(index) {
      if (index > 0) {
        this.$emit('move-up', index);
      }
    },
    moveDown(index) {
      if (index < this.images.length - 1) {
        this.$emit('move-down', index);
      }
    },
  },
};
</script>
