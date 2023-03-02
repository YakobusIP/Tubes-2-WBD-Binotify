import { PrismaClient } from "@prisma/client";
import { XMLParser } from 'fast-xml-parser';
import { v4 as uuidv4 } from "uuid";
import fs from "fs";
import axios from "axios";

const prisma = new PrismaClient();

// Function to save file into /src/uploads folder
const saveFile = (file) => {
    return new Promise(function(resolve, reject) {
        let fileFormat = file.originalname.split('.');
        let savePath = uuidv4() + '.' + fileFormat[fileFormat.length - 1];

        fs.writeFile('./src/uploads/' + savePath, file.buffer , function(err) {
            if (err) {
                return reject(err);
            }

            return resolve(savePath);
        });
    });
}

// Function to insert song based on user_id
export const addSong = async (req, res) => {
    let audio_path = await saveFile(req.file);
    try {
        const song = await prisma.song.create({
            data: {
                judul: req.body.judul,
                audio_path: audio_path,
                penyanyi: {
                    connect: {
                        user_id: parseInt(req.body.penyanyi_id)
                    }
                }
            }
        });
        res.status(201).json(song);
    } catch (err) {
        console.error(err);
    }
}

// Function to fetch all songs data based on user_id
export const getAllSongs = async (req, res) => {
    var limit = 5;
    var offset = (req.params.page - 1) * limit;
    try {
        const songs = await prisma.song.findMany({
            where: {
                penyanyi_id: parseInt(req.params.user_id)
            },
            skip: offset,
            take: limit
        });

        const total = await prisma.song.count();
        var pageCount = Math.ceil(total/limit);
        res.status(200).json({
            songs: songs,
            pageCount: pageCount
        });
    } catch (err) {
        console.error(err);
    }
}

// Function to update songs based on song_id
export const updateSong = async (req, res) => {
    console.log(req.body);
    var audio_path;

    if (req.file !== undefined) {
        audio_path = await saveFile(req.file);
    }

    try {
        var song;
        if (req.file !== undefined) {
            const old_path = await prisma.song.findUnique({
                where: {
                    song_id: parseInt(req.body.song_id)
                },
                select: {
                    audio_path: true
                }
            })

            song = await prisma.song.update({
                where: {
                    song_id: parseInt(req.body.song_id)
                },
                data: {
                    judul: req.body.judul,
                    audio_path: audio_path
                }
            })
    
            fs.unlink('./src/uploads/' + old_path.audio_path, function(err) {
                if (err) {
                    throw(err);
                }
    
                console.log("Old file has been deleted");
            })
        } else {
            song = await prisma.song.update({
                where: {
                    song_id: parseInt(req.body.song_id)
                },
                data: {
                    judul: req.body.judul
                }
            })
        }

        res.status(200).json(song);
    } catch (err) {
        console.error(err);
    }
}

// Function to delete song based on song_id
export const deleteSong = async (req, res) => {
    try {
        const song = await prisma.song.delete({
            where: {
                song_id: parseInt(req.params.song_id)
            }
        })

        fs.unlink('./src/uploads/' + song.audio_path, function(err) {
            if (err) {
                throw(err);
            }

            console.log("Old file has been deleted");
        })
        console.log("Song successfully deleted");
        res.status(200).json(song);
    } catch(err) {
        console.error(err);
    }
}

// Function to call SOAP WS for subscriber
export const getSongsForSubscriber = async (req, res) => {
    const params = req.params;
    console.log(req.params);

    const xml = `
    <soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/" xmlns:ser="http://services.soap.binotify/">
        <soapenv:Header/>
        <soapenv:Body>
        <ser:validateSubscription>
            <creator_id>${params.creator_id}</creator_id>
            <subscriber_id>${params.subscriber_id}</subscriber_id>
        </ser:validateSubscription>
        </soapenv:Body>
    </soapenv:Envelope>
    `;
    // Check SOAP WS to see if user is subscribed to the singer
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
    const validState = JSON.parse(rawJSON["S:Envelope"]["S:Body"]["ns2:validateSubscriptionResponse"]["return"]);

    console.log("XML Response: " + response);
    console.log("Validation: " + validState);
    
    try {
        var songs = [];
        var songCount = 0;
        if (validState) {
            songs = await prisma.song.findMany({
                where: {
                    penyanyi_id: parseInt(req.params.creator_id)
                }
            });
            songCount = await songs.length;
        }
        res.status(200).json({
            params: params,
            validState: validState,
            songs: songs,
            songCount: songCount,
        });
    } catch (err) {
        console.error(err);
    }
}
