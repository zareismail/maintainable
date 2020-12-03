Nova.booting((Vue, router, store) => {
  router.addRoutes([
    {
      name: 'maintenable',
      path: '/maintenable',
      component: require('./components/Tool'),
    },
  ])
})
