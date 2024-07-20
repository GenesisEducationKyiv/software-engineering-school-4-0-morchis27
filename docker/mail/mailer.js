const { Kafka } = require('kafkajs');
const nodemailer = require('nodemailer');

const kafka = new Kafka({
    clientId: 'email-service',
    brokers: ['kafka:9092']
});

const consumer = kafka.consumer({ groupId: 'email' });

const transporter = nodemailer.createTransport({
    host: 'mailhog',
    port: 1025,
    secure: false,
});

const run = async () => {
    await consumer.connect();
    await consumer.subscribe({ topic: 'mail-requests', fromBeginning: true });

    await consumer.run({
        eachMessage: async ({ topic, partition, message }) => {
            const emailDetails = JSON.parse(message.value.toString()).data;

            const mailOptions = {
                from: emailDetails.from || 'asdasd@example.com',
                to: emailDetails.to || 'asd1asd@example.com',
                subject: emailDetails.subject || 'some subject',
                text: emailDetails.text || 'some content',
                html: emailDetails.html || '<p>some content</p>',
            };

            try {
                await transporter.sendMail(mailOptions);
            } catch (error) {
            }
        }
    });
};

run().catch(console.error);
