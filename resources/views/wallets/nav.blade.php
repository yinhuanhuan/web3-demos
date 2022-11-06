<el-menu
    :default-active="activeIndex"
    class="el-menu-demo"
    mode="horizontal"
    background-color="#545c64"
    text-color="#fff"
    active-text-color="#ffd04b"
>
    <el-menu-item index="0">
        <el-link :underline="false" href="/wallets">Wallet</el-link>
    </el-menu-item>

    <el-menu-item index="1">
        <el-link :underline="false" href="/wallets/create">Create Wallet</el-link>
    </el-menu-item>

    <el-menu-item index="2">
        <el-link :underline="false" href="/wallets/import">Import Wallet</el-link>
    </el-menu-item>

    <el-menu-item index="3">
        <el-link :underline="false" href="/wallets/balance">Balance</el-link>
    </el-menu-item>

    <el-menu-item index="4">
        <el-link :underline="false" href="/wallets/transaction">Transaction</el-link>
    </el-menu-item>

    <el-menu-item index="5">
        <el-link :underline="false" href="/wallets/token_balance">Token Balance</el-link>
    </el-menu-item>

    <el-menu-item index="6">
        <el-link :underline="false" href="/wallets/send_tokens">Send Tokens</el-link>
    </el-menu-item>
</el-menu>
