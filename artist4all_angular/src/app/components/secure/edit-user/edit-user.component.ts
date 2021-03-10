import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { UserService } from 'src/app/services/user.service';

@Component({
  selector: 'app-edit-user',
  templateUrl: './edit-user.component.html',
  styleUrls: ['./edit-user.component.css'],
  providers: [UserService]

})
export class EditUserComponent implements OnInit {

  constructor(private _userService: UserService, private _router: Router) { }

  id:number;
  name:string = "";
  surname1:string = "";
  surname2:string = "";
  email:string = "";
  username:string = "";
  img:string = "";

  ngOnInit(): void {
    this._userService.isAuthenticated(localStorage.getItem('token')).subscribe(
      (result) => {
        if (result["response"] != 'Autorizado') this._router.navigate(['/login']);
        this._userService.getUserData(localStorage.getItem("token")).subscribe(
          (result) => {
            this.id = result["id"];
            this.name = result["name"];
            this.surname1 = result["surname1"];
            this.surname2 = result["surname2"];
            this.email = result["email"];
            this.username = result["username"];
            this.img = result["img"];
          },
          (error) => {
            console.log(error);
          }
        )
      },
      (error) => {
          console.log(error);
      }
    )
  }

  nameForm:string = "";
  surname1Form:string = "";
  surname2Form:string = "";
  emailForm:string = "";
  /*
  usernameForm:string = "";
  passwordForm:string = "";
  img:string = "";
  */
/* TODO para un about me  */

  editUser() {

  }

}
