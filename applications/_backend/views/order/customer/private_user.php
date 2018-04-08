<h3>Podaci o kupcu</h3>
<li>
    <p> 
        <span style="color:#494949; font-weight: bold;">Kupac:</span> <?= $order->getUser()->getFirstName().' '.$order->getUser()->getLastName() ?><br />
        <span style="color:#494949; font-weight: bold;">Adresa:</span> <?= $order->getUser()->getAddress() ?><br /> 
        <span style="color:#494949; font-weight: bold;">Poštanski broj:</span> <?= $order->getUser()->getPostalCode()->getPostalCode() ?><br /> 
        <span style="color:#494949; font-weight: bold;">Grad:</span> <?= $order->getUser()->getPostalCode()->getCity() ?><br /> 
        <span style="color:#494949; font-weight: bold;">Telefon:</span> <?= $order->getUser()->getPhone() ?><br /> 
        <span style="color:#494949; font-weight: bold;">Email:</span> <?= $order->getUser()->getEmail() ?><br /> 
        <span style="color:#494949; font-weight: bold;">Način plaćanja:</span> <?php $payment_types = unserialize(PAYMENT_TYPE); echo $payment_types[$order->getPaymentType()] ?><br /> 
        <?php if ($order->getPaymentType() == 1){ ?> 
        <span style="color:#494949; font-weight: bold;">Tip kartice:</span> <?= $order->getCardType() != '' ? $card_types[$order->getCardType()] : ''; ?><br /> 
        <span style="color:#494949; font-weight: bold;">Identifikacioni kod:</span> <?= $order->getAuthCode(); ?><br /> 
        <span style="color:#494949; font-weight: bold;">ID plaćanja:</span> <?= $order->getPaymentID()?><br /> 
        <span style="color:#494949; font-weight: bold;">ID transakcije:</span> <?= $order->getTransactionID()?><br /> 
        <?php } ?>
    </p>
</li> 