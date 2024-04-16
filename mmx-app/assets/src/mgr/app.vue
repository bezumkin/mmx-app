<template>
  <div class="overflow-auto vh-100 p-3">
    <h4 class="m-0">{{ $t('menu_name') }}</h4>
    <div class="small text-secondary">{{ $t('menu_desc') }}</div>

    <BNav tabs class="mt-3">
      <BNavItem v-for="(section, idx) in sections" :key="idx" :to="{name: section.route}" active-class="active">
        {{ $t(section.title) }}
      </BNavItem>
    </BNav>
    <div class="main-wrapper mb-md-5">
      <RouterView />
    </div>
  </div>
</template>

<script setup lang="ts">
const sections = [{title: 'models.item.title_many', route: 'index'}]
</script>

<style scoped lang="scss">
.main-wrapper {
  border: 1px solid var(--bs-border-color);
  border-top: 0;
  border-radius: 0 0 var(--bs-border-radius) var(--bs-border-radius);
}
</style>
