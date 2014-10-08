
<form name="frm-register" class="" action="" method="post">
    <div class="form-item col-md-12">
        <label>Họ tên(*)</label>
        <input type="text" name="fullname" class="txt required" value="<?php echo ( isset( $_POST['fullname'] ) ? $_POST['fullname'] : null ) ?>" />
    </div>
    <div class="form-item col-md-12">
        <label>Email(*)</label>
        <input type="text" name="email" class="txt required validateEmail" value="<?php echo ( isset ($_POST['email']) ? $_POST['email'] : null ) ?>" />
    </div>
    <div class="form-item col-md-12">
        <label>Mật khẩu(*)</label>
        <input type="password" name="password" class="txt required" />
    </div>
    <div class="form-item col-md-12">
        <label>Nhập lại mật khẩu(*)</label>
        <input type="password" name="confirmPassword" class="txt required" />
    </div>
    <div class="form-item col-md-12">
        <label>Địa chỉ</label>
        <input type="text" name="address" class="txt required" />
    </div>
    <div class="form-item col-md-12">
        <label>Tỉnh/Thành phố</label>
        <select name="province" id="province" class="cbb required">
            <option value="none">Tỉnh/Thành phố</option>
            <?php foreach($cities as $city): ?>
            <option value="<?php echo $city->value; ?>"><?php echo $city->name; ?></option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="form-item col-md-12">
        <label>Quận/Huyện</label>
        <select name="district" id="district" class="cbb required">
            <option value="none">Quận/Huyện</option>
        </select>
    </div>
    <div class="form-item col-md-12">
        <label>Phường/Xã</label>
        <select name="wards" id="wards" class="cbb required">
            <option value="none">Phường/Xã</option>
        </select>
    </div>
    <div class="form-item col-md-12">
        <label>Di Động(*)</label>
        <input type="text" name="phone" class="txt required validateNumber" />
    </div> 
    <div class="form-item col-md-12">
        <label></label>
        <input type="checkbox" name="check" />
        Đồng ý với thỏa thuận sử dụng
    </div>
    <div class="form-item end">
        <input type="submit" class="btn-register" name="submit" value="Tạo tài khoản" />
    </div>
</form>