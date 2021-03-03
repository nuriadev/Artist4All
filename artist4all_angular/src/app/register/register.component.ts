import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { User } from '../models/user';
import { UserService } from '../services/user.service';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css'],
  providers: [UserService]
})
export class RegisterComponent implements OnInit {

  constructor(private _userService: UserService, private _router: Router) { }

  ngOnInit(): void {
  }

  name:string = "";
  surname1:string = "";
  surname2:string = "";
  email:string = "";
  username:string = "";
  password:string = "";
  passwordConfirm:string = "";
  type_user:number;

  // todo hacerlo con modelo user
  // todo: comprobar que las contraseñas sean iguales
  register() {
    this._userService.register(new User(this.name, this.surname1, this.surname2, this.email, this.username, this.password, this.type_user)).subscribe(
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
