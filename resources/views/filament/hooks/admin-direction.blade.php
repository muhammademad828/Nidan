@if(app()->getLocale() === 'ar')
<style>
    html { direction: rtl; }
    body { font-family: 'Tajawal', 'Cairo', sans-serif; }
</style>
<script>
    document.documentElement.setAttribute('dir', 'rtl');
    document.documentElement.setAttribute('lang', 'ar');
</script>
@else
<script>
    document.documentElement.setAttribute('dir', 'ltr');
    document.documentElement.setAttribute('lang', 'en');
</script>
@endif
