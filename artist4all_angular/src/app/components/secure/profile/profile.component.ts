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

  ngOnInit(): void {
    this._userService.isAuthenticated(localStorage.getItem('token')).subscribe(
      (result) => {
        if (result["response"] != 'Autorizado') this._router.navigate(['/login']);
      },
      (error) => {
          console.log(error);
      }
    )
  }

}
