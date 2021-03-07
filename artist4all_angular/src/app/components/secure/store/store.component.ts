import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { UserService } from 'src/app/services/user.service';

@Component({
  selector: 'app-store',
  templateUrl: './store.component.html',
  styleUrls: ['./store.component.css'],
  providers: [UserService]
})
export class StoreComponent implements OnInit {

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
