<template>
  <MmxModal v-model="record" v-bind="properties">
    <template #form-fields>
      <FormsItem v-model="record" />
    </template>
  </MmxModal>
</template>

<script setup lang="ts">
const record = ref({})

const properties = {
  url: 'mgr/items/' + useRoute().params.id,
  title: $t('models.item.title_one'),
  updateKey: 'mgr-items',
  method: 'patch',
}

try {
  record.value = await useGet(properties.url)
} catch (e) {
  console.error(e)
  useError()
}
</script>
