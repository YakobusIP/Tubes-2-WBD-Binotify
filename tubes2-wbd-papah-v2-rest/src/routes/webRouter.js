import express from "express";
const router = express.Router();
import {
    register,
    login,
    logout
} from "../controllers/authController.js";

import {
    addSong,
    getAllSongs,
    updateSong,
    deleteSong,
    getSongsForSubscriber
} from "../controllers/songController.js";

import {
    getAllUser,
    getSubscriptionList,
    approveRequest,
    rejectRequest
} from "../controllers/userController.js";

import {
    authenticateToken
} from "../middleware/auth.js";

// Routing routes

// Available without authentication
router.post('/register', register); // register
router.post('/login', login); // login
router.delete('/logout', logout); // logout

// Require authentication
// Non Admin side
router.get('/song-list/:user_id/:page', authenticateToken, getAllSongs);
router.post('/add-song', authenticateToken, addSong);
router.patch('/update-song', authenticateToken, updateSong);
router.delete('/delete-song/:song_id', authenticateToken, deleteSong);
router.get('/singer-song-list/:creator_id/:subscriber_id', getSongsForSubscriber);

// Admin side
router.get('/user-list', getAllUser);
router.get('/get-subscription-list', authenticateToken, getSubscriptionList);
router.post('/approve-request', authenticateToken, approveRequest);
router.post('/reject-request', authenticateToken, rejectRequest);

export default router;
