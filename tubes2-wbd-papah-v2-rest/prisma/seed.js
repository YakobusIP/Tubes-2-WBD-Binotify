import { PrismaClient } from '@prisma/client';
const prisma = new PrismaClient();
import { hashPassword } from '../src/controllers/authController.js';

async function main() {
    const admin = await prisma.user.create({
        data: {
            email: 'admin@gmail.com',
            username: 'admin',
            name: 'admin',
            password: await hashPassword("admin"),
            isAdmin: true
        }
    })
}

main()
    .then(async () => {
        await prisma.$disconnect()
    })
    .catch(async (e) => {
        console.error(e)
        await prisma.$disconnect()
        process.exit(1)
    })