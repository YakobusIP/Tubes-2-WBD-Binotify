import { createClient } from 'redis';

export default class redisController {
    constructor(host, port) {
        this.expires = 86400;
        this.host = host;
        this.port = port;
        this.url = 'redis://' + this.host + ':' + this.port;
        this.client = createClient({
            url: this.url
        })

        this.client.connect();

        this.client.on('error', (err) => {
            console.log('Error connecting to redis', err);
        })

        this.client.on('ready', () => {
            console.log('Redis cache ready...');
        })

        this.client.on('connect', () => {
            console.log('Connected successfully...');
        })
    }
    
    async setData(key, value) {
        this.client.setEx(key, this.expires, value);
    }

    async getData(key) {
        let value = await this.client.get(key.toString());
        return JSON.parse(value);
    }
}