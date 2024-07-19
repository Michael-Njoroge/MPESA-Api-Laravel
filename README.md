# M-Pesa Integration in Laravel

This document outlines the process for integrating Safaricom's M-Pesa API with a Laravel application. The integration includes functionalities for STK Push, C2B Simulations, and B2C payments.

## Table of Contents

- [Prerequisites](#prerequisites)
- [Environment Configuration](#environment-configuration)
- [Controller Methods](#controller-methods)
  - [Get Access Token](#get-access-token)
  - [Register URLs](#register-urls)
  - [Simulate Transaction](#simulate-transaction)
  - [STK Push](#stk-push)
  - [B2C Request](#b2c-request)
- [Helper Methods](#helper-methods)
  - [Make HTTP Request](#make-http-request)
  - [Format Phone Number](#format-phone-number)

## Prerequisites

1. **Laravel Installation**: Ensure you have a Laravel application set up.
2. **M-Pesa Account**: Obtain credentials from Safaricom.
3. **Certbot**: For securing your local environment with SSL/TLS (if necessary).

## Environment Configuration

1. **Add Environment Variables**

   Update your `.env` file with the following M-Pesa configuration:

   ```env
   MPESA_ENVIRONMENT=0  # Use 1 for production
   MPESA_CONSUMER_KEY=your_consumer_key
   MPESA_CONSUMER_SECRET=your_consumer_secret
   MPESA_SHORTCODE=your_shortcode
   MPESA_PASSKEY=your_passkey
   MPESA_B2C_INITIATOR=your_initiator_name
   MPESA_B2C_PASSWORD=your_b2c_password
   MPESA_TEST_URL=https://your-ngrok-url
