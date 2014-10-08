<?php

/* 
 * Display usermeta
 */
?>

<h3>User Meta</h3>
<table class="form-table" id="v-table">
    <tbody>
        <tr>
            <th><label>Tỉnh/Thành phố</label></th>
            <td>
                <select name="province" id="province" class="cbb">
                    <option value="none">Tỉnh/Thành phố</option>
                    <?php foreach($cities as $city): ?>
                    <?php 
                    $selected = '';
                    if($city->value == $provinceCurrent){
                        $selected = 'selected = selected';
                    } ?>
                    <option <?php echo $selected; ?> value="<?php echo $city->value; ?>"><?php echo $city->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label>Quận/Huyện</label></th>
            <td>
                <select name="district" id="district" class="cbb">
                    <option value="none">Quận/Huyện</option>
                    <?php foreach($districts as $district): ?>
                    <?php 
                    $selected = '';
                    if($district->value == $districtCurrent){
                        $selected = 'selected = selected';
                    } ?>
                    <option <?php echo $selected; ?> value="<?php echo $district->value; ?>"><?php echo $district->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label>Phường/Xã</label></th>
            <td>
                <select name="wards" id="wards" class="cbb">
                    <option value="none">Phường/Xã</option>
                    <?php foreach($wards as $ward): ?>
                    <?php 
                    $selected = '';
                    if($ward->value == $wardCurrent){
                        $selected = 'selected = selected';
                    } ?>
                    <option <?php echo $selected; ?> value="<?php echo $ward->value; ?>"><?php echo $ward->name; ?></option>
                    <?php endforeach; ?>
                </select>
            </td>
        </tr>
        <tr>
            <th><label>Địa chỉ</label></th>
            <td><input type="text" class="regular-text" name="address" value="<?php echo $user_meta[0]['address']; ?>" /></td>
        </tr>
        <tr>
            <th><label>Số điện thoại</label></th>
            <td><input type="text" class="regular-text" name="phone" value="<?php echo $user_meta[0]['phone']; ?>" /></td>
        </tr>
    </tbody>
</table>
