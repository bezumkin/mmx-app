<template>
  <div>
    <h3>Items ({{ total }})</h3>
    <ul>
      <li v-for="item in items" :key="item.id">{{ item.title }}</li>
    </ul>
  </div>
</template>

<script setup lang="ts">
const url = 'web/items'
const total = ref(0)
const items = ref<Record<string, any>[]>([])

onMounted(async () => {
  try {
    const data = await useGet(url, {sort: 'id', dir: 'desc'})
    total.value = data.total
    items.value = data.rows

    useToastSuccess('Items was loaded successfully!')
  } catch (e) {}
})
</script>
