import http from 'k6/http';
import {check, sleep} from 'k6';

export let options = {
    vus: 10,
    iterations: 40,
    thresholds: {
        http_req_failed: [{
            threshold: 'rate<=0.05',
            abortOnFail: true,
        }],
        http_req_duration: ['p(95)<=4000', 'med<=2000'],
        checks: ['rate>=0.99'],
    },
};

export default function () {
    const emailUsername = Math.random().toString(36).substring(2, 7);
    const email = emailUsername.toLowerCase() + '@example.com';

    const payload = {
        email: email,
    }

    const params = {
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
    };

    let response = http.post('http://web/api/subscribe', payload, params);
    check(response, {'200 returned': (r) => r.status === 200});

    sleep(Math.random() * 5);
}
