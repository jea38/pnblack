<?php
include 'main.php';
// Retrieve the total number of messages for the current day
$stmt = $pdo->prepare('SELECT COUNT(*) AS total FROM messages WHERE cast(submit_date as DATE) = cast(now() as DATE)');
$stmt->execute();
$messages_today_total = $stmt->fetchColumn();
// Retrieve the total number of messages
$stmt = $pdo->prepare('SELECT COUNT(*) AS total FROM messages');
$stmt->execute();
$messages_total = $stmt->fetchColumn();
// Retrieve the total number of lsaccounts for the current day
$stmt = $pdo->prepare('SELECT a.*, (SELECT COUNT(*) FROM messages WHERE account_id = a.id) AS messages_total FROM lsaccounts a WHERE cast(a.registered as DATE) = cast(now() as DATE) ORDER BY a.registered DESC');
$stmt->execute();
$users = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Retrieve the total number of accounts
$stmt = $pdo->prepare('SELECT COUNT(*) AS total FROM accounts');
$stmt->execute();
$users_total = $stmt->fetchColumn();
// Dashboard template below
?>
<?=template_admin_header('Dashboard', 'dashboard')?>

<div class="content-title">
    <h2>Dashboard</h2>
</div>

<div class="dashboard">
    <div class="content-block stat">
        <div class="data">
            <h3>New Messages</h3>
            <p><?=number_format($messages_today_total)?></p>
        </div>
        <i class="fas fa-comments"></i>
        <div class="footer">
            <i class="fa-solid fa-rotate fa-xs"></i>Total messages for today
        </div>
    </div>

    <div class="content-block stat">
        <div class="data">
            <h3>Total Messages</h3>
            <p><?=number_format($messages_total)?></p>
        </div>
        <i class="fas fa-comments"></i>
        <div class="footer">
            <i class="fa-solid fa-rotate fa-xs"></i>Total messages
        </div>
    </div>

    <div class="content-block stat">
        <div class="data">
            <h3>New Users</h3>
            <p><?=number_format(count($users))?></p>
        </div>
        <i class="fas fa-users"></i>
        <div class="footer">
            <i class="fa-solid fa-rotate fa-xs"></i>Users registered today
        </div>
    </div>

    <div class="content-block stat">
        <div class="data">
            <h3>Total Users</h3>
            <p><?=number_format($users_total)?></p>
        </div>
        <i class="fas fa-users"></i>
        <div class="footer">
            <i class="fa-solid fa-rotate fa-xs"></i>Total users
        </div>
    </div>
</div>

<div class="content-title">
    <h2>New Users</h2>
</div>

<div class="content-block">
    <div class="table">
        <table>
            <thead>
                <tr>
                    <td class="responsive-hidden">#</td>
                    <td>Email</td>
                    <td>Name</td>
                    <td class="responsive-hidden">Role</td>
                    <td class="responsive-hidden">Status</td>
                    <td class="responsive-hidden">Sent Messages</td>
                    <td class="responsive-hidden">Last Seen</td>
                    <td class="responsive-hidden">Registered</td>
                    <td>Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                <tr>
                    <td colspan="10" style="text-align:center;">There are no users</td>
                </tr>
                <?php else: ?>
                <?php foreach ($users as $a): ?>
                <tr>
                    <td class="responsive-hidden"><?=$a['id']?></td>
                    <td><?=htmlspecialchars($a['email'], ENT_QUOTES)?></td>
                    <td><?=htmlspecialchars($a['full_name'], ENT_QUOTES)?></td>
                    <td class="responsive-hidden"><?=$a['role']?></td>
                    <td class="responsive-hidden"><?=$a['status']?></td>
                    <td class="responsive-hidden">
                        <?php if ($_SESSION['chat_account_role'] == 'Admin'): ?>
                        <a href="chat_logs.php?acc_id=<?=$a['id']?>" class="link1"><?=$a['messages_total']?></a>
                        <?php else: ?>
                        <?=$a['messages_total']?>
                        <?php endif; ?>
                    </td>
                    <td class="responsive-hidden" title="<?=$a['last_seen']?>"><?=time_elapsed_string($a['last_seen'])?></td>
                    <td class="responsive-hidden"><?=date('Y-m-d H:ia', strtotime($a['registered']))?></td>
                    <td><a href="account.php?id=<?=$a['id']?>" class="link1">Edit</a></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?=template_admin_footer()?>