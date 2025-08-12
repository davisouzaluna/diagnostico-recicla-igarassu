<template>
   <main>
      <section>
         <slot/>
      </section>
   </main>
</template>

<style lang="scss">
main {
  margin: 8rem auto 0 auto;
  width: 70vw;

  @media (max-width: 576px) {
    margin: 8rem auto 0 auto;
    width: 90vw;
}
}
</style>