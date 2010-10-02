<div id="loginbox">
  <form action="<?php echo $this->H('Html')->url('/users/login');?>" method="post">
    
    <p>
      <label>Email:</label><br /> 
      <input type="text" name="User[email]" style="width:99%;" />
    </p>
    
    <p>
      <label>Password:</label><br /> 
      <input type="password" name="User[password]" style="width:99%;" />
      <button type="submit">login</button>
    </p>
    
  </form>
</div>