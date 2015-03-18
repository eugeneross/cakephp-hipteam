<!-- Most basic view ever. Feel free to change this to fit your design :) -->
<div class="container">
	<h1>Here's your team:</h1>
	<table class="table">
		<tr>
			<th>Name</th>
			<th>Mention name</th>
		</tr>

		<? foreach($team as $members): ?>
			<? foreach($members as $member): ?>
				<? if(!empty($member)): ?>
					<?= $this->Element('member', array('member' => $member)); ?>
				<? endif; ?>
			<? endforeach; ?>
		<? endforeach; ?>

	</table>
</div>
