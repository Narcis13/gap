/*
|--------------------------------------------------------------------------
| Routes
|--------------------------------------------------------------------------
|
| This file is dedicated for defining HTTP routes. A single file is enough
| for majority of projects, however you can define routes in different
| files and just make sure to import them inside this file. For example
|
| Define routes in following two files
| ├── start/routes/cart.ts
| ├── start/routes/customer.ts
|
| and then import them inside `start/routes.ts` as follows
|
| import './routes/cart'
| import './routes/customer''
|
*/

import Route from '@ioc:Adonis/Core/Route'
//import Application from '@ioc:Adonis/Core/Application'

Route.get('/', async ({ view }) => {
  return view.render('welcome')
})

Route.get('/transferdininternet', async ({ request,view }) => {
  let qs=request.qs()
  //console.log(qs)
  let src= qs.src||''
  return view.render('dininternet',{src})
})


Route.post('/incarcadininternet', async ({ request ,view}) => {
  const fisiere = request.files('fisiere')
  let src=request.body().src
  let ip=request.ip()
  for (let fisier of fisiere) {
    //console.log(request.ip())
    await fisier.moveToDisk('./uploads',{name:fisier.clientName})
   // await fisier.move(Application.tmpPath('uploads'))
  }
  return await view.render('succes',{ip})
})