// import type { HttpContextContract } from '@ioc:Adonis/Core/HttpContext'
const puppeteer = require('puppeteer');

export default class ScrapersController {

    public async searchmap(){
        (async ()=>{
            console.log('searching...')
            const url='https://www.google.ro/maps/search/clinici+medicale+Oradea/@47.0745735,21.8674052,12z'
            const browser = await puppeteer.launch();
            const page = await browser.newPage();
            await page.goto(url);
            const text = await page.evaluate(() => document.body.innerText);
            const links = await page.evaluate(() =>
                Array.from(document.querySelectorAll('a[data-value="Site"]'), (e) => e.href)
            );
    
            await browser.close();
            console.log('stopped searching',text,links)
        })()



    }

}
