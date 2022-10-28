<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Wallet Address</title>

        <script src="https://lf6-cdn-tos.bytecdntp.com/cdn/expire-1-M/vue/3.2.31/vue.global.min.js" type="application/javascript"></script>
        <script src="https://lf26-cdn-tos.bytecdntp.com/cdn/expire-1-M/ethers/5.5.4/ethers.umd.min.js" type="application/javascript"></script>
        <script src="https://lf9-cdn-tos.bytecdntp.com/cdn/expire-1-M/axios/0.26.0/axios.min.js" type="application/javascript"></script>
    </head>
    <body class="antialiased">
        <div id="app">
            <h3>@{{ title }}</h3>
            
            <table>
                <tr>
                    <th>Address</th>
                    <td>
                        <input v-model="address" placeholder="address" />
                    </td>
                </tr>
                <tr>
                    <th>Balance</th>
                    <td>
                        <input v-model="balance" placeholder="balance" />
                    </td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <button @click="queryBalance()">Query Balance</button>
                        <span>@{{ msg }}</span>
                    </td>
                </tr>
            </table>
        </div>

        <script>
            const { createApp } = Vue

            createApp({
                data() {
                    return {
                        title: 'Wallet Address',
                        address: "",
                        msg: '',
                        balance: 0,
                    }
                },
                methods: {
                    // 查询钱包余额
                    async queryBalance() {
                        // 检查是否为钱包地址
                        if (!ethers.utils.isAddress(this.address)) {
                            this.msg = 'Address format error.'
                            return
                        }

                        try {
                            const response = await axios.get('/api/wallets/balance', {
                                params: {
                                    address: this.address,
                                }
                            });
                            let data = response.data

                            // jsonrpc api是否报错
                            if (data.hasOwnProperty('error')) {
                                this.msg = data.error.code + ' ' + data.error.message
                                return
                            }

                            this.msg = 'query balance successful.'
                            this.balance = ethers.utils.formatUnits(ethers.BigNumber.from(data.result))
                        } catch (error) {
                            this.msg = error
                        }
                    },
                }
            }).mount('#app')
        </script>
    </body>
</html>
