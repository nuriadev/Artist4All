import { Component, OnInit } from '@angular/core';
import { UserService } from '../service/user.service';

@Component({
  selector: 'app-register',
  templateUrl: './register.component.html',
  styleUrls: ['./register.component.css'],
  providers: [UserService]
})
export class RegisterComponent implements OnInit {

  constructor(private _userService: UserService) { }

  ngOnInit(): void {
  }

  email:string = "";
  password:string = "";
  passwordConfirm:string = "";

  // todo hacerlo con modelo user
  // todo: comprobar que las contraseñas sean iguales
  register() {
    this._userService.register(this.email, this.password).subscribe(
      (result) => {
        console.log(result);
      }, (error) => {
        console.log(error);
      }
    )
  }



}
