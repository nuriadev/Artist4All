import { Component, OnInit } from '@angular/core';
import { UserService } from '../service/user.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.component.html',
  styleUrls: ['./login.component.css'],
  providers: [UserService]
})
export class LoginComponent implements OnInit {

  constructor(private _userService: UserService) { }

  ngOnInit(): void {
  }

  email:string = "";
  password:string = "";
 // todo hacerlo con una clase user
  login() {
    this._userService.login(this.email, this.password).subscribe(
      (result) => {
        console.log(result);
      }, (error) => {
        console.log(error);
      }
    )
  }


}
