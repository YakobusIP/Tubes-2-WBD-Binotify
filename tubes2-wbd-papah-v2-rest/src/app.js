import express from "express";
import cors from "cors";
import multer from "multer";
const upload = multer();
import webRouter from "./routes/webRouter.js";
import cookieParser from "cookie-parser";
import path from 'path';
import { fileURLToPath } from "url";
  
const app = express();
const PORT = 4000;
app.use(express.json());

// Use default cors middleware
var whitelist = ['http://localhost:3000', 'http://localhost:8080', 'http://localhost:7000'];
app.use(cors({
    credentials: true, 
    origin: true
}));
app.use(cookieParser());

// For parsing multipart/form-data
const __filename = fileURLToPath(import.meta.url);
const __dirname = path.dirname(__filename);
app.use(upload.single('audio_file'));
app.use(express.static(path.join(__dirname, 'uploads/')));
app.use(webRouter);
  
app.listen(PORT, (error) =>{
    if(!error)
        console.log("Server is Successfully Running, and App is listening on port "+ PORT)
    else 
        console.log("Error occurred, server cannot start", error);
    }
);