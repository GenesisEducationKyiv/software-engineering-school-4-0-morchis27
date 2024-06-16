import http from 'k6/http';
import { check, sleep } from 'k6';

export let options = {
    vus: 10,
    iterations: 40,
    thresholds: {
        http_req_failed: [{
            threshold: 'rate<=0.05',
            abortOnFail: true,
        }],
        http_req_duration: ['p(95)<=3000', 'med<=1000'],
        checks: ['rate>=0.99'],
    },
};

export default function () {
    let response = http.get('http://web/api/rate');
    check(response, { '200 returned': (r) => r.status === 200 });

    sleep(Math.random() * 5);
}
