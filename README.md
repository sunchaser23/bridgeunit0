# Bridge Unit0

A PHP script for bridging UNIT0 tokens between the Unit0 blockchain and Waves blockchain.

## Requirements

- PHP 7.2 or higher
- Composer package manager
- Access to Unit0 and Waves blockchains
- Private keys for both Unit0 and Waves accounts
- Bridge contract address

## Usage

The script bridges UNIT0 tokens from Unit0 blockchain to Waves blockchain.

Basic usage:

## Installation

1. Clone this repository
2. Install dependencies:

>  composer install

## Configuration

1. Create config.php

> cp config.sample.php config.php

The script requires configuration in `config.php` with the following parameters:

- `unit0.pk`: Your Unit0 private key
- `unit0.bridgecontract`: The bridge contract address
- `waves.pk`: Your Waves private key  
- `waves.chain`: Chain type ('T' for testnet, 'W' for mainnet)

## Bridge Unit0


> php bridgeunit0.php 1 # Bridges 1 UNIT0
> 
> php bridgeunit0.php ALL # Bridges all UNIT0 except 1
