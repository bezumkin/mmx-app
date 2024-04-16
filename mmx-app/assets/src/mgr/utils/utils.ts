export function formatDate(val: string | null): string {
  if (!val) {
    return ''
  }
  const date = new Date(val)
  return date.toLocaleDateString() + ' ' + date.toLocaleTimeString()
}
