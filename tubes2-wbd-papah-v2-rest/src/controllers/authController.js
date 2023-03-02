import { PrismaClient } from '@prisma/client';
import bcryptjs from 'bcryptjs';
import redisController from '../redis/redisController.js';
import jwt from 'jsonwebtoken';

const prisma = new PrismaClient();
const redis = new redisController('bnmo-rest-redis', 6379);

// Register function
export const register = async (req, res) => {
    try {
        var hashedPass = await hashPassword(req.body.password)
        var isUnameUnique = true;
        var isEmailUnique = true;

        const checkUsername = await prisma.user.findFirst({
            where: {
                username: req.body.username
            }})

        if(checkUsername !== null){
            isUnameUnique = false;
        }

        const checkEmail = await prisma.user.findFirst({
            where: {
                email: req.body.email
            }
        })

        if(checkEmail !== null){
            isEmailUnique = false;
        }

        if(isEmailUnique && isUnameUnique){
            const user = await prisma.user.create({
                data: {
                    email: req.body.email,
                    password: hashedPass,
                    username: req.body.username,
                    name: req.body.name
                }
            });
            var user_list;
    
            var value = await redis.getData('users');
            // If null, this is the first data, create an empty array and push
            if (value === null) {
                console.log('Creating new array...');
                user_list = [];
                user_list.push(user);
            } else {
                console.log('Pulling array from redis...');
                user_list = value;
                user_list.push(user);
            }
    
            redis.setData('users', JSON.stringify(user_list));
    
            res.status(201).json(user);
        }else{
            if(isEmailUnique){
                res.status(405).json({message: "username is taken"}) // uname not unique
            }else{
                res.status(406).json({message: "email is taken"}) // email not unique
            }
        }
            
    } catch (err) {
        console.error(err)
    }
}

// Login function
export const login = async (req, res) => {
    console.log(req.body);
    var username = req.body.username;
    var hashedPass = req.body.password;
    try{
        const userTaken = await prisma.user.findUnique({
            where:{
                username: username
            }
        })
        if(userTaken !== null){
            const user = { 
                user_id : userTaken.user_id,
                username : userTaken.username,
                isAdmin : userTaken.isAdmin
            }
    
            if(bcryptjs.compareSync(hashedPass, userTaken.password)){
                const accessToken = generateAccessToken(user);
                res.cookie("token", accessToken, {maxAge:86400000});
                res.json({accessToken: accessToken, user: user});
                console.log("bener");
            } else{
                console.log("Wrong Password");
                res.status(403).json({message: "Wrong Password"})
            }
        }else{
            res.status(405).json({message: "Username is not registered"})
        }


    } catch(err) {
        console.error(err);
    }
}

// Logout function
export const logout = async (req, res) => {
    res.clearCookie("token");
    res.json({message:"Logout successful"})
}

// Function to generate jwt access token
function generateAccessToken(user){
    return jwt.sign(user, process.env.ACCESS_TOKEN_SECRET)
}

// Function to hash a string password
export async function hashPassword(password){
    const salt = await bcryptjs.genSalt()
    const hashed = await bcryptjs.hash(password, salt)
    return hashed
}


