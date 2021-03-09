import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { UserService } from 'src/app/services/user.service';

@Component({
  selector: 'app-profile',
  templateUrl: './profile.component.html',
  styleUrls: ['./profile.component.css'],
  providers: [UserService]
})
export class ProfileComponent implements OnInit {

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

}
