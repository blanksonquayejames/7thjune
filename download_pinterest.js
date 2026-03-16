const puppeteer = require('puppeteer');
const fs = require('fs');
const https = require('https');
const path = require('path');

// To execute Laravel code from Node, we will just read DB manually or pass products from PHP.
// Better yet, I'll write a PHP script that calls this Node script for each product.
const args = process.argv.slice(2);
const query = args[0] || 'laptop aesthetic';
const savePath = args[1] || 'storage/app/public/products/test.jpg';

(async () => {
    const browser = await puppeteer.launch({ headless: true });
    const page = await browser.newPage();
    
    // Set a realistic user agent
    await page.setUserAgent('Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/114.0.0.0 Safari/537.36');
    
    console.log(`Searching Pinterest for: ${query}`);
    await page.goto(`https://www.pinterest.com/search/pins/?q=${encodeURIComponent(query)}`, { waitUntil: 'networkidle2' });
    
    // Wait for images to load
    try {
        await page.waitForSelector('img', { timeout: 10000 });
        
        // Extract the first high quality image source
        const imageUrl = await page.evaluate(() => {
            const imgs = Array.from(document.querySelectorAll('img'));
            for (let img of imgs) {
                if (img.src && img.src.includes('i.pinimg.com') && !img.src.includes('75x75')) {
                    // Try to get a higher resolution version if possible (e.g. 736x)
                    return img.src.replace(/236x|400x/, '736x');
                }
            }
            return null;
        });

        if (imageUrl) {
            console.log(`Found image: ${imageUrl}`);
            
            // Download the image
            const file = fs.createWriteStream(savePath);
            https.get(imageUrl, function(response) {
                response.pipe(file);
                file.on('finish', function() {
                    file.close();  
                    console.log(`Saved to ${savePath}`);
                    browser.close();
                });
            }).on('error', function(err) {
                fs.unlink(savePath, () => {}); 
                console.error(`Error downloading: ${err.message}`);
                browser.close();
                process.exit(1);
            });
        } else {
            console.log('No valid image found.');
            browser.close();
            process.exit(1);
        }
    } catch (err) {
        console.error('Error finding images:', err);
        browser.close();
        process.exit(1);
    }
})();
