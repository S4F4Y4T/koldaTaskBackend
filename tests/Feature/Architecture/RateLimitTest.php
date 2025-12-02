<?php

// rate limit
it('Enforces rate limiting on the login route', function () {
    foreach (range(1, 3) as $i) {
        $response = $this->getJson(route('v1.health'));

        if ($i > 2) {
            $response->assertStatus(429);
        } else {
            $response->assertStatus(200);
        }
    }
});
