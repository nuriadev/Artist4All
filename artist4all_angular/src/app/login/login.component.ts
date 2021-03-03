import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { UserService } from '../services/user.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
  providers: [UserService]
})
export class LoginComponent implements OnInit {

  constructor(private _userService: UserService, private _router: Router) { }

  ngOnInit(): void {
  }

  email:string = "";
  password:string = "";
 // todo hacerlo con una clase user
  login() {
    this._userService.login(this.email, this.password).subscribe(
      (result) => {
          if (result != "Usuario incorrecto") {
            localStorage.setItem("token", result['token']);
            localStorage.setItem("currentUser",JSON.stringify(result["user"]));
            this._router.navigate(['home']);
            console.log(result);
          }
      }, (error) => {
        console.log(error);
      }
    )
  }


}
