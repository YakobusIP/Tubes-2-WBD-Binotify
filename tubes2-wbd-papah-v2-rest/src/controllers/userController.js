import axios from 'axios';
import { XMLParser } from 'fast-xml-parser';
import { PrismaClient } from '@prisma/client';
import redisController from '../redis/redisController.js';

const prisma = new PrismaClient();
const redis = new redisController('bnmo-rest-redis', 6379);

// Function to get all singers
export const getAllUser = async (req, res) => {
    var user_list = [];
    console.log('Checking REDIS client...');
    user_list = await redis.getData('users');

    // No data is in the cache
    if (user_list === null) {
        console.log('REDIS client is empty, accessing DATABASE...');
        try {
            const users = await prisma.user.findMany({
                where: {
                    isAdmin: false
                },
                select: {
                    user_id: true,
                    name: true,
                    username: true
                }
            });
    
            const total = await prisma.user.count();
            res.status(200).json({
                users: users,
                total: total
            });
        } catch (err) {
            console.error(err);
        }
    } else {
        res.status(200).json({
            users: user_list,
            total: user_list.length
        });
    }
}

// Function to get all subscription requests in SOAP database
export const getSubscriptionList = async (req, res) => {
    const xml = `
    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://services.soap.binotify/">
        <soapenv:Header/>
        <soapenv:Body>
            <ser:getSubscriptionRequests/>
        </soapenv:Body>
    </soapenv:Envelope>
    `

    const request = await axios.post("http://bnmo-soap-web:7000/subscription", xml, {
        headers: {
            'Content-Type' : 'text/xml',
            'API-Key': process.env.REST_API_KEY
        }
    }).catch(err => {
        console.log(err);
    })
    const response = await request.data;
    const parser = new XMLParser();
    const rawJSON = parser.parse(response);
    const data = JSON.parse(rawJSON["S:Envelope"]["S:Body"]["ns2:getSubscriptionRequestsResponse"]["return"]);
    const subsList = data.records;
    
    console.log("XML Response: "+ response);
    console.log("Subscription List: "+ subsList);
    res.status(200).json(subsList);
}

// Function to approve subscriber
export const approveRequest = async (req, res) => {
    const data = {
        'creator_id': req.body.creator_id,
        'subscriber_id': req.body.subscriber_id
    };
    const xml = `
    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://services.soap.binotify/">
        <soapenv:Header/>
        <soapenv:Body>
        <ser:acceptSubscriptionRequest>
            <creator_id>${data.creator_id}</creator_id>
            <subs_id>${data.subscriber_id}</subs_id>
        </ser:acceptSubscriptionRequest>
        </soapenv:Body>
    </soapenv:Envelope>
    `;    
    const request = await axios.post("http://bnmo-soap-web:7000/subscription", xml, {
        headers: {
            'Content-Type': 'text/xml',
            'API-Key': process.env.REST_API_KEY
        }
    }).catch(err => {
        console.log(err);
    });
    const response = await request.data;
    const parser = new XMLParser();
    const rawJSON = parser.parse(response);
    const approvalState = JSON.parse(rawJSON["S:Envelope"]["S:Body"]["ns2:acceptSubscriptionRequestResponse"]["return"]);

    console.log("XML Response: " + response);
    console.log("Approval: " + approvalState);
    
    res.status(200).json({
        dataReceived: data,
        approvalState: approvalState
    });
}

// Function to reject subscriber
export const rejectRequest = async (req, res) => {
    const data = {
        'creator_id': req.body.creator_id,
        'subscriber_id': req.body.subscriber_id
    };
    const xml = `
    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://services.soap.binotify/">
        <soapenv:Header/>
        <soapenv:Body>
        <ser:rejectSubscriptionRequest>
            <creator_id>${data.creator_id}</creator_id>
            <subs_id>${data.subscriber_id}</subs_id>
        </ser:rejectSubscriptionRequest>
        </soapenv:Body>
    </soapenv:Envelope>
    `;    
    const request = await axios.post("http://bnmo-soap-web:7000/subscription", xml, {
        headers: {
            'Content-Type': 'text/xml',
            'API-Key': process.env.REST_API_KEY
        }
    }).catch(err => {
        console.log(err);
    });
    const response = await request.data;
    const parser = new XMLParser();
    const rawJSON = parser.parse(response);
    const rejectionState = JSON.parse(rawJSON["S:Envelope"]["S:Body"]["ns2:rejectSubscriptionRequestResponse"]["return"]);

    console.log("XML Response: " + response);
    console.log("Rejection: " + rejectionState);
    
    res.status(200).json({
        dataReceived: data,
        rejectionState: rejectionState
    });
}
