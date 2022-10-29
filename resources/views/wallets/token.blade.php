<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Token</title>

        <script src="https://lf6-cdn-tos.bytecdntp.com/cdn/expire-1-M/vue/3.2.31/vue.global.min.js" type="application/javascript"></script>
        <script src="https://lf26-cdn-tos.bytecdntp.com/cdn/expire-1-M/ethers/5.5.4/ethers.umd.min.js" type="application/javascript"></script>
        <script src="https://lf9-cdn-tos.bytecdntp.com/cdn/expire-1-M/axios/0.26.0/axios.min.js" type="application/javascript"></script>
        <script src="/js/abi.js"></script>
    </head>
    <body class="antialiased">
        <div id="app">
            <h3>@{{ title }}</h3>
            
            <table>
                <tr>
                    <th>Contract Address</th>
                    <td>
                        <input v-model="contractAddress" placeholder="contract address" />
                    </td>
                </tr>
                <tr>
                    <th>Wallet Address</th>
                    <td>
                        <input v-model="walletAddress" placeholder="wallet address" />
                    </td>
                </tr>
                <tr>
                    <th>Token Balance</th>
                    <td>@{{ tokenBalance }}</td>
                </tr>
                <tr>
                    <th></th>
                    <td>
                        <button @click="queryTokenBalance()">Query Token Balance</button>
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
                        title: 'Token',
                        msg: '',
                        contractAddress: '',
                        walletAddress: '',
                        tokenBalance: 0,
                    }
                },
                methods: {
                    // 查询token余额
                    async queryTokenBalance() {
                        // 检查是否为智能合约地址
                        if (!ethers.utils.isAddress(this.contractAddress)) {
                            this.msg = 'Contract Address format error.'
                            return
                        }

                        // 检查是否为钱包地址
                        if (!ethers.utils.isAddress(this.walletAddress)) {
                            this.msg = 'Wallet Address format error.'
                            return
                        }

                        // 编码调用的abi函数及参数数据
                        var interface = new ethers.utils.Interface(abi_erc20)
                        var rawData = interface.encodeFunctionData('balanceOf',[
                            this.walletAddress,
                        ])

                        try {
                            const response = await axios.post('/api/wallets/tokenBalance', {
                                contractAddress: this.contractAddress,
                                rawData: rawData,
                            })
                            let data = response.data

                            // jsonrpc api是否报错
                            if (data.hasOwnProperty('error')) {
                                this.msg = data.error.code + ' ' + data.error.message
                                return
                            }

                            this.msg = 'query token balance successful.'
                            this.tokenBalance = ethers.BigNumber.from(data.result)
                        } catch (error) {
                            this.msg = error
                        }
                    },
                }
            }).mount('#app')
        </script>
    </body>
</html>
