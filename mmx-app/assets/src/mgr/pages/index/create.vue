<template>
  <MmxModal v-model="record" v-bind="properties">
    <template #form-fields>
      <FormsItem v-model="record" />
    </template>
  </MmxModal>
</template>

<script setup lang="ts">
const record = ref({
  title: '',
  active: true,
})

const properties = {
  url: 'mgr/items',
  title: $t('models.item.title_one'),
  method: 'put',
}
</script>
